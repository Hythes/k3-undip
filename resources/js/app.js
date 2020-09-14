import "./bootstrap";
import Vue from "vue";
import App from "./layouts/App.vue";
import router from "./router.js";
import vuetify from "./vuetify";

const vm = new Vue({
    vuetify,
    router,
    el: "#app",
    render: (h) => h(App),
});
