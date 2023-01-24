user_name.onblur  = function () {
    fetch('/check.php', {
        method: 'post',
        headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({cmd: "check_user_name", value: this.value})
    }).then(function (response) {return response.json(); }).then(function (data) {
        if(!data.success) {
            submit.disabled = true;
            user_name.style.border = "2px ridge red";
            username_error.textContent = data.error;
        } else {
            submit.disabled = false;
            user_name.style.border = "2px ridge green";
            username_error.textContent = "";
        }
    });
};

email.onblur  = function () {
    fetch('/check.php', {
        method: 'post',
            headers: {
            'Accept': 'application/json, text/plain, */*',
                'Content-Type': 'application/json'
        },
        body: JSON.stringify({cmd: "check_email", value: this.value})
    }).then(function (response) {return response.json(); }).then(function (data) {
        if(!data.success) {
            submit.disabled = true;
            email.style.border = "2px ridge red";
            email_error.textContent = data.error;
        } else {
            submit.disabled = false;
            email.style.border = "2px ridge green";
            email_error.textContent = "";
        }
    });
};