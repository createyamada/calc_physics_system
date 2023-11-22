<template>
    <div class="container">
        <!-- ヘッダー -->
        <header>
            <slot name="header"></slot>
        </header>

        <hr class="hr_line">

        <!-- サイドバー -->
        <nav class="side_menu">
            <div class="scroll">
                <slot name="side_menu"></slot>
            </div>

        </nav>

        <!-- メイン -->
        <main>
            <div class="main_disp">
                <slot name="main"></slot>
            </div>
        </main>

        <hr class="hr_line">
        
        <!-- フッター -->
        <footer class="footer">
            <slot name="footer"></slot>
        </footer>

    </div>
</template>

<script setup>
</script>

<style scoped>
.container {
    display: grid;
    grid-template-columns: var(--side-menu-width) calc(100% - var(--side-menu-width));
    grid-template-rows: var(--header-height) auto auto;
    grid-template-areas: 
        "header  header"
        "side_menu main"
        "side_menu footer" 
    ;
    min-height: 100vh;
}

header {
    grid-area: header;
    display: flex;
    height: var(--header-height);
    background-color: white;
    align-items: center;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
}

nav {
    grid-area: side_menu;
    background-color: #C0CDDC;
    width: var(--side-menu-width);
    position: fixed;
    top: var(--header-height);
    left: 0;
    bottom: 0;
}

main {
    grid-area: main;
    background-color: var(--bg-color);
}

footer {
    grid-area: footer;
    border-top: 5px var(--footer-border-color);
    background-color: var(--footer-bg-color);
}

.scroll {
    width: 100%;
    height: 100%;
    overflow: hidden auto;
}

.hr_line {
    color: var(--dark-color);
}

</style>
