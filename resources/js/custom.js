window.debounceCall = (fn, delay = 1000) => {
    let timerId = null;
    return (...args) => {
        clearTimeout(timerId);
        timerId = setTimeout(() => fn(...args), delay);
    };
};

window.isBraveBrowser = async function () {
    return (navigator.brave && await navigator.brave.isBrave());
}

// Function to esign the student's information, the student's information is passed as a parameter in the form of a JSON string
window.esignStudentInfo = async function (domain) {
    const signJSONUrl = domain + "/api/getJWS";
    const studentData = document.getElementById("studentInfo").value.trim();
    var jws = null;
    try {
        const parsedJson = JSON.parse(studentData);
        console.log('Parsed data:', parsedJson);
        const response = await fetch(signJSONUrl, {
            method: "POST",
            body: JSON.stringify(parsedJson),
            headers: {
                'Accept': 'application/json'
            }
        });
        const responseData = await response.json();
        if (!response.ok) {
            if (response.status == 0) {
                throw new Error(
                    `Unable to connect to the server at ${signJSONUrl}. Please check your internet connection.`
                );
            }
            throw new Error(responseData.message || "An error occurred");
        }
        console.log(responseData.message);
        jws = responseData.jws;
        //console.log('JWS String:', jws);
    } catch (error) {
        console.error('An error occurs:', error);
        if (error.message) {
            if (error.message.includes('Unexpected token')) {
                alert('Invalid JSON data, please check the student information');
            } else if (error.message.includes('Unable to connect')) {
                alert(error.message);
            } else if (error.message.includes('NetworkError')) {
                alert('Network error, please check your internet connection');
            } else if (error.message.includes('Failed to fetch')) {
                isBraveBrowser().then(isBrave => {
                    if (isBrave) {
                        alert(
                            `It seems like you're using Brave. Please disable Shields for this site to allow local digital signing. Also make sure your DigiSignServer is up and running at` +
                            domain +
                            ` or go to settings of the DigiSignServer app and make sure the domain of the website is listed in the allowed origin list.`
                        );
                    } else {
                        alert(
                            `Failed to fetch ${signJSONUrl}, please check if DigiSignServer is up and running at ${domain} ` +
                            `or go to settings of the DigiSignServer app and make sure the domain of the website is listed in the allowed origin list.`
                        );
                    }
                });

            } else {
                alert(error.message);
            }
        } else {
            alert('An error occurred while signing the JSON data, may be the JSON data is invalid');
        }
        jws = null;
    }
    return jws;
}

// ✅ Define and expose globally
window.testAlert = function (message = 'Hello from testAlert!') {
    alert(message);
};

/** opening and  closing custom modals */
window.openCustomModal = function (modalId) {

    const modal = document.getElementById(modalId);
    if (modal) {
        const box = modal.querySelector(".modal-body");

        modal.classList.remove("hidden");
        modal.classList.add("flex");

        setTimeout(() => {
            if (box) {
                box.classList.remove("opacity-0", "scale-95");
                box.classList.add("opacity-100", "scale-100");
            }

        }, 10);
    }
}

window.closeCustomModal = function (modalId) {

    const modal = document.getElementById(modalId);
    if (modal) {
        const box = modal.querySelector(".modal-body");
        if (box) {
            box.classList.remove("opacity-100", "scale-100");
            box.classList.add("opacity-0", "scale-95");
        }
        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 300); // duration matches CSS transition
    }
}

var openModalBtns = document.querySelectorAll("button[data-toggle='modal']");
openModalBtns.forEach(element => {
    element.addEventListener('click', (e) => {
        openCustomModal(element.getAttribute("data-target"));
    });
});

document.querySelectorAll("button.close-modal").forEach((btn) => {
    btn.addEventListener('click', (e) => {
        let modalElement = btn.closest("div.modal");
        if (modalElement) {
            closeCustomModal(modalElement.getAttribute("id"));
        }
    });
});

/** opening and closing a custom alert box */
window.openCustomAlertBox = function (alertBoxId) {
    const alertBox = document.getElementById(alertBoxId);
    if (alertBox) {
        //alertBox.classList.remove("hidden");
        alertBox.classList.add("show-alert-box");
        alertBox.scrollIntoView(true);
    }
    else {
        console.warn("Alert box is not found.");
    }
}

window.closeCustomAlertBox = function (alertBoxElement) {
    if (alertBoxElement) {
        alertBoxElement.classList.remove("show-alert-box");
    }
    else {
        console.error("Alert box not found");
    }
}

/** Alert Box */
var openAlertBtns = document.querySelectorAll("[data-toggle='alert']");
openAlertBtns.forEach(element => {
    element.addEventListener('click', (e) => {
        openCustomAlertBox(element.getAttribute("data-target"));
    });
});

document.querySelectorAll(".close-alert").forEach((btn) => {
    btn.addEventListener('click', (e) => {
        let alertBoxElement = btn.closest(".alert-box");
        if (alertBoxElement) {
            closeCustomAlertBox(alertBoxElement);
        }
        else {
            console.error("Alert box not found");
        }
    });
});
/** end of opening and closing custom alert box */







