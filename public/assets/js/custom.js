function showAlert(type, message) {
    if (type) {
        $("#success-alert").text(message);
        $("#success-alert").addClass("animate__animated animate__fadeInRight");
        $("#success-alert").removeClass("d-none");
    } else {
        $("#error-alert").removeClass("d-none");
        $("#error-alert").text(message);
        $("#error-alert").addClass("animate__animated animate__fadeInRight");
    }

    setTimeout(() => {
        $(".top-alert").addClass("d-none");
    }, 4000);
}

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
