
<template>
    <PhysicsPageLayout>
        <template v-slot:header>
            <CustomHeader/>
        </template>

        <template v-slot:side_menu>
            <CustomSideMenu/>
        </template>

        <template v-slot:main>
            <router-view></router-view>
        </template>

        <template v-slot:footer>
            
        </template>
    </PhysicsPageLayout>
</template>

<script setup>
import PhysicsPageLayout from '@/Components/Layouts/PhysicsPageLayout.vue';
import CustomHeader from '@/Components/CustomHeader.vue';
import CustomSideMenu from '@/Components/CustomSideMenu.vue';

</script>

<style scoped>
</style>