<template>
  <FormComponent msg="Welcome to Your Vue.js App"/>
</template>

<script>
import FormComponent from './components/FormComponent.vue'

export default {
  name: 'App',
  components: {
    FormComponent: FormComponent
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 30px;
}
</style>
