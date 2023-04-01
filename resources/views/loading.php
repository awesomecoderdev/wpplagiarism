<div id="plLoadingScreen" class="fixed inset-0 z-[99999999999] h-screen overflow-hidden hidden lg:block bg-zinc-100 duration-500">
</div>
<script>
    const plLoadingScreen = document.getElementById('plLoadingScreen');
    setTimeout(() => {
        if (plLoadingScreen) {
            plLoadingScreen.classList.add("opacity-0");
            plLoadingScreen.remove();
        }
    }, 1000);
</script>