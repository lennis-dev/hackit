const UNDERSCORE_BLINK_DELAY = 500;

const codeInput = document.getElementById("code-input");
for (let i = 0; i < 8; i++) {
    const span = document.createElement("span");
    span.textContent = " ";
    span.classList.add("u");
    codeInput.appendChild(span);
}
const codeInputChars = codeInput.getElementsByTagName("span");

var code = [];

window.addEventListener("keydown", async (ev) => {
    if (ev.ctrlKey)
        return;
    if (ev.key === "Backspace") {
        if (code.length > 0) {
            code.pop();
            codeInputChars[code.length].textContent = " ";
            if (code.length < 8 - 1)
                codeInputChars[code.length + 1].classList.add("u");
        }
    } else if (ev.key.length === 1 && code.length < 8) {
        code.push(ev.key);
        codeInputChars[code.length - 1].textContent = ev.key;
        codeInputChars[code.length - 1].classList.add("u");
        if (code.length === 8) {
            if (typeof validate === "function") {
                if (await validate(code.join(""))) {
                    window.location = "?code=" + code.join("");
                }
                else {
                    wrongCode();
                }
            } else {
                window.location = "?code=" + code.join("");
            }
        }
        ev.preventDefault();
    }
});

function wrongCode(wrongCode = "") {
    if (wrongCode !== "") {
        for (let i = 0; i < 8; i++) {
            codeInputChars[i].textContent = wrongCode[i];
        }
    }

    codeInput.classList.add("error");
    setTimeout(() => {
        code = [];
        for (let i = 0; i < 8; i++) {
            codeInputChars[i].textContent = " ";

        }
        codeInput.classList.remove("error");
    }, 1000);
}

window.setInterval(() => {
    if (code.length < 8) {
        codeInputChars[code.length].classList.toggle("u");
    }
}, UNDERSCORE_BLINK_DELAY);
