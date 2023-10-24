export default {
    onIdle() {
        this.$store.commit("userStore/setDefaultUrl", window.location.pathname);
        this.$store.dispatch("userStore/logoff", "");
        this.$router.push({ name: "Logoff" });
    }
};
