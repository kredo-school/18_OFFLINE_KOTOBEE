public/js/streak.js
document.addEventListener("DOMContentLoaded", () => {
    const counter = document.getElementById("streakCounter");
    if (!counter) return;

    const target = parseInt(counter.dataset.streak);
    let current = 0;

    const interval = setInterval(() => {
        current++;
        counter.textContent = current;

        if (current >= target) {
            clearInterval(interval);
        }
    }, 50);
});
