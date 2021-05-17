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


// Generic Ajax GET function
function goGet(url) {
    return new Promise((resolve, reject) => {
        $.ajax({
                type: "GET",
                url,
            })
            .then((res) => {
                resolve(res);
            })
            .catch((err) => {
                reject(err);
            });
    });
}

// Generic Ajax POST function
function goPost(url, data) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url,
            data,
            processData: false,
            contentType: false,
            statusCode: {
                200: (res) => {
                    res.status = 200;
                    resolve(res);
                },
                500: (err) => {
                    err.status = 500;
                    reject(err);
                },
                404: (err) => {
                    err.status = 404;
                    reject(err);
                },
                419: (err) => {
                    err.status = 419;
                    reject(err);
                },
            },
        });
    });
}

// Handle form error
function handleFormRes(res, form = false, prefix = false, modalAlert = false) {
    if (res.success === undefined) {
        return true;
    }
    if (res.status === 200) {
        if (!res.success) {
            errors = res.message;

            if (typeof errors === "object") {
                if (modalAlert) {
                    //If modal alert is to be used
                    let errArr = [];
                    for (const [key, value] of Object.entries(errors)) {
                        [...value].forEach((m) => {
                            errArr.push(m);
                        });
                    }
                    let uniqueChars = [...new Set(errArr)];
                    e = document.getElementById(modalAlert);
                    e.innerHTML = "<ul>";
                    uniqueChars.forEach((m) => {
                        e.innerHTML += `<li>${m}</li>`;
                    });
                    e.innerHTML += "</ul>";

                    // Show error modal after filling it with data
                    $("#" + modalAlert)
                        .parent()
                        .parent()
                        .parent()
                        .modal("show");
                } else {
                    for (const [key, value] of Object.entries(errors)) {
                        e = prefix ?
                            document.getElementById(prefix + "-" + key) :
                            document.getElementById(key);
                        e.innerHTML = "";
                        [...value].forEach((m) => {
                            e.innerHTML += `<p>${m}</p>`;
                        });
                    }
                }
                return false;
            } else {
                if (form) {
                    $("#" + form).html(errors);
                    $("#" + form).removeClass("d-none");
                } else {
                    showAlert(false, errors);
                }
                return false;
            }
        } else {
            return true;
        }
    } else {
        if (form) {
            $("#" + form).html("Oops! Something's not right. Try Again");
            $("#" + form).removeClass("d-none");
        } else {
            showAlert(false, "Oops! Something's not right. Try Again");
        }
        return false;
    }
}