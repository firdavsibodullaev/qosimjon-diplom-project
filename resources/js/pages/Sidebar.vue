<template>
    <a-menu v-model:selectedKeys="selectedKeys"
            theme="dark"
            v-model:openKeys="openKeys"
            :items="routes"
            mode="inline"/>
</template>
<script>
import MenuItem from "@/components/MenuItem.vue";
import SubMenu from "@/components/SubMenu.vue";
import hasPermission from "@/utils/hasPermission";
import routes from "@/libs/routes";

export default {
    name: 'Sidebar',
    components: {SubMenu, MenuItem},
    data() {
        return {
            selectedKeys: [],
            openKeys: []
        };
    },
    beforeMount() {
        const activeRoutes = this.getPathUntilCurrentRoute(this.routesMethod(), this.$route);
        let subMenu = [];
        let menuItem = [];

        function getKeys(sub, item, routes) {
            if (routes.openedChildren && routes.openedChildren.length > 0) {
                sub.push(routes.key);
                getKeys(sub, item, routes.openedChildren[0]);
            } else {
                item.push(routes.key);
            }

            return [sub, item];
        }

        let keys = getKeys(subMenu, menuItem, activeRoutes);
        this.openKeys = keys[0];
        this.selectedKeys = keys[1];
    },
    computed: {
        routes() {
            return this.filterSidebarRoutes(routes).map(route => this.prepareForMenu(route));
        },
        loading() {
            return this.$store.getters['sidebar/getLoading'];
        }
    },
    methods: {
        filterSidebarRoutes(routes) {
            return routes.filter(route => {

                if (route.meta.sidebar && route.children) {
                    route.sidebarChildren = this.filterSidebarRoutes(route.children);
                }

                return route.meta.sidebar && hasPermission(route.meta.role);
            });
        },
        getPathUntilCurrentRoute(routes, current) {
            for (let i = 0; i < routes.length; i++) {
                let route = routes[i];

                if (route.title === current.name) {
                    route.target = true;
                    return route;
                }

                if (route.children && route.children.length > 0) {
                    let routesTree = this.getPathUntilCurrentRoute(route.children, current);
                    if (typeof routesTree === 'object') {

                        let currentRouteTree = route.children.filter(item => {
                            return item.target;
                        });

                        if (currentRouteTree.length > 0) {
                            route.openedChildren = currentRouteTree;
                            route.target = true;
                        }

                        return route;
                    }
                }
            }
        },
        prepareForMenu(route) {
            let result = {
                key: `menu-key-${route.name}`,
                label: route.meta.text,
                title: route.name,
                disabled: this.loading
            }

            if (route.sidebarChildren) {
                result.children = route.sidebarChildren.map(item => this.prepareForMenu(item));
            } else if (route.name !== this.$route.name) {
                result.onClick = () => this.$router.push({name: route.name});
            }
            return result;
        },
        routesMethod() {
            return this.routes;
        }
    }
}
</script>
