const snackbarStore = {
    namespaced:true,
    state: {
        msg: 'test',
        snackbar:false,
    },
    mutations: {
        setMsg(state,payload) {
            state.msg = payload;
            state.snackbar = true ;
        },
        clearStore(state) {
            state.msg='';
            state.snackbar = false ;
        },
    },


}

export default snackbarStore;
