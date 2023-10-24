import {isPermission} from './permission-filters';

export const hasPermission = {
    bind(el, {name, value}, vnode) {
        if(name == 'can-show'){
            name = 'show';
        }
        let hasPermission =  isPermission(vnode.context.$store.state.permissionStore.userPermissions, value, name);
        if(!hasPermission) {
            el.style.display = 'none';
        }
    },
    update(el, {name, value}, vnode) {
        let hasPermission =  isPermission(vnode.context.$store.state.permissionStore.userPermissions, value, name);
        if(!hasPermission) {
            el.style.display = 'none';
        }else {
            if(el.localName == "span" || el.localName == "button" || el.localName == "i"){
                el.style.display = 'inline-block';
            }else if(el.localName == "a"){
                el.style.display = 'inline';
            } else{
                el.style.display = 'block';
            }
        }
    },
};
