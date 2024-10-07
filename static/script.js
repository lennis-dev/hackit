const UNDERSCORE_BLINK_DELAY = 500;

const menuIcon = document.getElementById('menu-icon');
const navMenu = document.querySelector('nav'); 

menuIcon.addEventListener('click', () => {
    navMenu.classList.toggle('active');
});

const codeInput = document.getElementById("code-input");
for (let i = 0; i < 8; i++) {
    const span = document.createElement("span");
    span.textContent = " "; 
    span.classList.add("u"); 
    codeInput.appendChild(span);
}

const codeInputChars = codeInput.getElementsByTagName("span");
let code = []; 
// Listen for keydown events
window.addEventListener("keydown", handleInput);

async function handleInput(ev) {
    if (ev.ctrlKey) return; 

    if (ev.key === "Backspace") {
        if (code.length > 0) {
            code.pop(); 
            codeInputChars[code.length].textContent = " "; 
            if (code.length < 8 - 1) {
                codeInputChars[code.length + 1].classList.add("u"); 
            }
        }
    } else if (ev.key.length === 1 && code.length < 8) {
        code.push(ev.key); // Add new character to code
        codeInputChars[code.length - 1].textContent = ev.key; 
        codeInputChars[code.length - 1].classList.add("u"); 

        if (code.length === 8) {
            if (typeof validate === "function") {
                if (await validate(code.join(""))) {
                    window.location = "?code=" + code.join(""); 
                } else {
                    console.log("Code validation failed");
                    codeInput.classList.add("error"); 
                }
            }
        }

        if (code.length < 8) {
            codeInputChars[code.length].classList.remove("u");
        }
    }
}

setInterval(() => {
    for (let i = 0; i < 8; i++) {
        codeInputChars[i].classList.toggle("u", !code[i]); 
    }
}, UNDERSCORE_BLINK_DELAY);
