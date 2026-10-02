if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

const isStandalone = window.matchMedia('(display-mode: standalone)').matches
    || window.navigator.standalone === true;
const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
let deferredPrompt = null;

const show = (selector) => {
    document.querySelectorAll(selector).forEach((el) => el.classList.remove('hidden'));
};

const hide = (selector) => {
    document.querySelectorAll(selector).forEach((el) => el.classList.add('hidden'));
};

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;
    hide('[data-pwa-fallback]');
    hide('[data-pwa-ios]');
});

window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    hide('[data-pwa-install]');
    hide('[data-pwa-ios]');
    hide('[data-pwa-fallback]');
    show('[data-pwa-installed]');
});

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (isStandalone) {
        document.body.classList.add('is-standalone');
        hide('[data-pwa-install-card]');
        show('[data-pwa-installed]');
    } else if (isIos) {
        show('[data-pwa-ios]');
    }

    document.querySelectorAll('[data-pwa-install]').forEach((button) => {
        button.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                await deferredPrompt.userChoice;
                deferredPrompt = null;
                return;
            }

            if (isIos) {
                show('[data-pwa-ios]');
                return;
            }

            show('[data-pwa-fallback]');
        });
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            sidebar?.classList.toggle('-translate-x-full');
            overlay?.classList.toggle('hidden');
        });
    });

    overlay?.addEventListener('click', () => {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    });

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-remove-companion]');
        if (button) {
            button.closest('[data-companion-row]')?.remove();
        }
    });

    const openModal = (id) => {
        const modal = document.getElementById(id);
        modal?.classList.add('is-open');
        document.body.classList.add('overflow-hidden');
        const focusable = modal?.querySelector('input, select, textarea, button');
        focusable?.focus();
    };

    const closeModal = (modal) => {
        modal?.classList.remove('is-open');
        if (!document.querySelector('.modal-overlay.is-open')) {
            document.body.classList.remove('overflow-hidden');
        }
    };

    document.querySelectorAll('[data-modal-open]').forEach((button) => {
        button.addEventListener('click', () => openModal(button.getAttribute('data-modal-open')));
    });

    document.querySelectorAll('[data-modal-close]').forEach((button) => {
        button.addEventListener('click', () => closeModal(button.closest('.modal-overlay')));
    });

    document.querySelectorAll('.modal-overlay').forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.is-open').forEach((modal) => closeModal(modal));
        }
    });

    document.querySelectorAll('.modal-overlay[data-open-on-load="1"]').forEach((modal) => {
        modal.classList.add('is-open');
        document.body.classList.add('overflow-hidden');
    });

    const money = (value) => 'Rs ' + Math.round(Number(value) || 0).toLocaleString('en-PK');
    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
    }[char]));
    const editForm = document.getElementById('employee-edit-form');
    const payForm = document.getElementById('employee-pay-form');
    const payMonth = document.getElementById('pay-month');
    const payAmount = document.getElementById('pay-amount');
    const historyList = document.getElementById('employee-history-list');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let currentEmployee = null;

    const remainingFor = (employee, month) => {
        const paid = Number(employee?.paid?.[month] || 0);
        const salary = Number(employee?.salary || 0);
        return Math.max(0, Math.round((salary - paid) * 100) / 100);
    };

    const fillFields = (form, employee) => {
        if (!form) {
            return;
        }
        ['name', 'phone', 'position', 'salary', 'hire_date', 'status', 'notes'].forEach((name) => {
            const field = form.elements.namedItem(name);
            if (field) {
                field.value = employee[name] ?? '';
            }
        });
    };

    const syncPayDue = () => {
        if (!currentEmployee || !payForm) {
            return;
        }
        const month = payMonth?.value;
        const due = remainingFor(currentEmployee, month);
        const dueEl = payForm.querySelector('[data-pay-due]');
        const nameEl = payForm.querySelector('[data-pay-name]');
        if (nameEl) {
            nameEl.textContent = currentEmployee.name;
        }
        if (dueEl) {
            dueEl.textContent = due > 0 ? `Due ${money(due)} of ${money(currentEmployee.salary)}` : `Settled for this month · salary ${money(currentEmployee.salary)}`;
        }
        if (payAmount && document.activeElement !== payAmount) {
            payAmount.value = due > 0 ? due : '';
        }
    };

    const openPay = (employee) => {
        if (!payForm) {
            return;
        }
        currentEmployee = employee;
        payForm.action = employee.pay_url;
        const payUrl = document.getElementById('pay-url');
        if (payUrl) {
            payUrl.value = employee.pay_url;
        }
        syncPayDue();
        openModal('employee-pay-modal');
    };

    const openEdit = (employee) => {
        editForm.action = employee.update_url;
        const actionField = document.getElementById('employee-edit-action');
        if (actionField) {
            actionField.value = employee.update_url;
        }
        fillFields(editForm, employee);
        openModal('employee-edit-modal');
    };

    const openHistory = (employee) => {
        const title = document.getElementById('employee-history-title');
        if (title) {
            title.textContent = employee.name;
        }
        if (!historyList) {
            return;
        }
        if (!employee.payments?.length) {
            historyList.innerHTML = '<p class="text-sm text-muted">No salary payments yet.</p>';
        } else {
            historyList.innerHTML = employee.payments.map((payment) => `
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-line p-4">
                    <div>
                        <div class="font-semibold">${escapeHtml(payment.amount_label)}</div>
                        <div class="mt-1 text-xs text-muted">${escapeHtml(payment.for_month_label)} · ${escapeHtml(payment.paid_on)}${payment.notes ? ' · ' + escapeHtml(payment.notes) : ''}</div>
                    </div>
                    <form method="POST" action="${escapeHtml(payment.void_url)}" onsubmit="return confirm('Remove this salary payment and its expense?')">
                        <input type="hidden" name="_token" value="${csrf}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-crimson hover:bg-rose-50" aria-label="Remove">
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 8h14M9.5 8V5.5h5V8M7 8l1 12h8l1-12"/></svg>
                        </button>
                    </form>
                </div>
            `).join('');
        }
        openModal('employee-history-modal');
    };

    document.querySelectorAll('[data-employee-action]').forEach((button) => {
        button.addEventListener('click', () => {
            const employee = JSON.parse(button.getAttribute('data-employee') || '{}');
            const action = button.getAttribute('data-employee-action');
            if (action === 'pay') {
                openPay(employee);
            } else if (action === 'edit') {
                openEdit(employee);
            } else if (action === 'history') {
                openHistory(employee);
            }
        });
    });

    payMonth?.addEventListener('change', syncPayDue);
});
