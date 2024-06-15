<script setup lang="ts">
import { reactive } from 'vue';
    interface User {
        login: string,
        password: string
    }
    const user = reactive<User>({
        login: 'login',
        password: 'password'
    })
</script>

<template>
    <div>
        {{ user.login }} 
    </div>
    <div>
        {{ user.password }} 
    </div>
</template>