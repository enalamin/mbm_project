import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import About from '../pages/About';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';

import Items from '../components/items/Items';
import AddItem from '../components/items/AddItem';
import EditItem from '../components/items/EditItem';

import Suppliers from '../components/suppliers/Suppliers';
import AddSupplier from '../components/suppliers/AddSupplier';
import EditSupplier from '../components/suppliers/EditSupplier';

import Requisitions from '../components/requisitions/Requisitions';
import AddRequisition from '../components/requisitions/AddRequisition';
import EditRequisition from '../components/requisitions/EditRequisition';
import ViewRequisition from '../components/requisitions/ViewRequisition';



import StockReceives from '../components/receives/StockReceives';
import AddStockReceive from '../components/receives/AddStockReceive';
import ViewStockReceive from '../components/receives/ViewStockReceive';
import ItemWiseStock from '../components/receives/ItemWiseStock';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'about',
        path: '/about',
        component: About
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'items',
        path: '/items',
        component: Items
    },
    {
        name: 'additem',
        path: '/items/add',
        component: AddItem
    },
    {
        name: 'edititem',
        path: '/items/edit/:id',
        component: EditItem
    },
    {
        name: 'suppliers',
        path: '/suppliers',
        component: Suppliers
    },
    {
        name: 'addsupplier',
        path: '/suppliers/add',
        component: AddSupplier
    },
    {
        name: 'editsupplier',
        path: '/suppliers/edit/:id',
        component: EditSupplier
    },
    {
        name: 'requisitions',
        path: '/requisitions',
        component: Requisitions
    },
    {
        name: 'addrequisition',
        path: '/requisitions/add',
        component: AddRequisition
    },
    {
        name: 'editrequisition',
        path: '/requisitions/edit/:id',
        component: EditRequisition
    },
    {
        name: 'viewrequisition',
        path: '/requisitions/view/:id',
        component: ViewRequisition
    },
    {
        name: 'stockreceive',
        path: '/stock-receive',
        component: StockReceives
    },
    {
        name: 'addstockreceive',
        path: '/stock-receive/add',
        component: AddStockReceive
    },
    {
        name: 'viewstockreceive',
        path: '/stock-receive/view/:id',
        component: ViewStockReceive
    },
    {
        name: 'itemstock',
        path: '/stock-receive/avilable-stock',
        component: ItemWiseStock
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
