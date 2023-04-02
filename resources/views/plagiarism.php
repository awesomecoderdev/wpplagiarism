<span class="plagiarism-percentage" id="value">40</span>
<div class="plagiarism-container">
    <div class="plagiarism-indicator plagiarism-red" style="--width: 40%;">
    </div>
    <div class="plagiarism-indicator plagiarism-green" style="width: 60%;">
    </div>
</div>

<script>
    function animateValue(e, n, a, t) {
        let i = null,
            l = m => {
                i || (i = m);
                let u = Math.min((m - i) / t, 1);
                e.innerHTML = Math.floor(u * (a + n) + n) + "%", u < 1 && window.requestAnimationFrame(l)
            };
        window.requestAnimationFrame(l)
    }
    animateValue(document.getElementById("value"), 0, 40, 500);
</script>