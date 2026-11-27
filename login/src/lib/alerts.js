// The one place SweetAlert2 is configured. Titles and messages are passed as plain text, never HTML.
import Swal from 'sweetalert2'

const dialog = Swal.mixin({
  buttonsStyling: false, reverseButtons: true, heightAuto: false, showCancelButton: true,
  customClass: { popup: 'alert-popup', actions: 'alert-actions', confirmButton: 'btn btn--primary', cancelButton: 'btn btn--secondary' },
})

// Resolves true only when the person presses the confirm button. Cancel is focused first.
export const confirmAction = ({ title, text, confirmText = 'Confirm', cancelText = 'Cancel' }) => dialog.fire({
  titleText: title, text, icon: 'warning', confirmButtonText: confirmText, cancelButtonText: cancelText, focusCancel: true,
}).then(result => result.isConfirmed)
