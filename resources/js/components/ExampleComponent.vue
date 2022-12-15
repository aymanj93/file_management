<template>
    <Folder :handle-click="handleClick" :show-menu="showMenu" :folders="this.path"/>

</template>

<script>
import Folder from "./Folder.vue";

    export default {
        components: {Folder},
        data() {
            return {
                path: [
                    {
                        id: 1,
                        name: 'root 1',
                        showFolder: false,
                        showMenu: false,
                        children: [
                            {
                                id: 2,
                                name: 'sub folder',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            },
                            {
                                id: 3,
                                name: 'sub folder s2',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            }
                        ]
                    },
                    {
                        id: 4,
                        name: 'root 1',
                        showFolder: false,
                        showMenu: false,
                        children: [
                            {
                                id: 5,
                                name: 'sub folder',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            },
                            {
                                id: 6,
                                name: 'sub folder s2',
                                showFolder: false,
                                showMenu: false,
                                children: []
                            }
                        ]
                    }

                ]
            }
        },
        methods: {
            handleClick (node) {
                console.log(node.id);
                node.showFolder = !node.showFolder;
                this.hideMenu(this.path);
            },
            showMenu(node) {
                this.hideMenu(this.path);
                node.showMenu = !node.showMenu
            },
            hideMenu(tree) {
                tree.forEach((branch) => {
                    branch.showMenu = false;
                    if (branch.children.length > 0) {
                        this.hideMenu(branch.children);
                    }
                });
            }
        },

    }
</script>
