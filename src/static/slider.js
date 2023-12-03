let sliderPosition = 0;

const moveSlider = (content, amount) => {
    const maxPosition = content.offsetWidth - content.parentElement.offsetWidth;
    sliderPosition += amount;

    if (sliderPosition > maxPosition) {
        sliderPosition = 0;
    } else if (sliderPosition < 0) {
        sliderPosition = maxPosition;
    }

    // Ako je sliderPosition pozitivan, slider se pomiče ulijevo, inače se pomiče udesno.
    content.style.left = `-${sliderPosition}px`;
}


document.addEventListener("DOMContentLoaded", () => {
    const content = document.querySelector(".slider-content-inner");
    if (content) {
        const leftButton = document.querySelector(".slider-button-left");
        const rightButton = document.querySelector(".slider-button-right");

        leftButton.addEventListener("click", () => moveSlider(content, -260))
        rightButton.addEventListener("click", () => moveSlider(content, 260))
    }
})