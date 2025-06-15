///////////////////////////////////////////////////////// function for view password
function togglePassword() {
    const pw = document.getElementById("password");
    const confirm = document.getElementById("confirm");

    if (pw && pw.type === "password") {
        pw.type = "text";
        if (confirm) confirm.type = "text";
    } else {
        pw.type = "password";
        if (confirm) confirm.type = "password";
    }
}

/////////////////////////////////////////////////////////