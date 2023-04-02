<!-- <div id="plLoadingScreen" class="fixed inset-0 z-[99999999999] h-screen overflow-hidden block bg-white duration-500">
</div>
<script>
    const plLoadingScreen = document.getElementById("plLoadingScreen");
    const plStyles = document.querySelectorAll("link");
    const plScripts = document.querySelectorAll("script");
    const plStyleTags = document.querySelectorAll("style");
    plStyles.forEach((link) => {
        const rel = link.getAttribute("rel")
        const id = link.getAttribute("id")

        if (rel == "stylesheet" && id != "wp-plagiarism-backend-css") {
            // console.log(link, rel)
            link.remove();
        }

    })
    plStyleTags.forEach((style) => {
        style.remove();
    })
    plScripts.forEach((script) => {
        if (script.getAttribute("src")) {
            script.remove();
        }
    })
    setTimeout(() => {
        if (plLoadingScreen) {
            plLoadingScreen.classList.add("opacity-0");
            plLoadingScreen.remove();
        }
    }, 1000);
</script> -->