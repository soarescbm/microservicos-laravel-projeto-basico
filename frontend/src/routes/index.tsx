import  {RouteProps} from "react-router-dom";
import Dashboard from "../pages/Dashboard";
import CategoryList from "../pages/category/PageList";
import CastMemberList from "../pages/castmember/PageList";
import GenreList from "../pages/genre/PageList";

export interface MyRouterProps  extends RouteProps {
    name: string;
    label: string;
}

const routes : MyRouterProps[] = [
    {
        name: 'dashboard',
        label: 'Dashboard',
        path: '/',
        component: Dashboard,
        exact: true

    },

    {
        name: 'categories.list',
        label: 'Listar Categorias',
        path: '/category',
        component: CategoryList,
        exact: true

    },
    {
        name: 'categories.create',
        label: 'Criar Categoria',
        path: '/category/create',
        component: CategoryList,
        exact: true

    },
    {
        name: 'castmembers.list',
        label: 'Lista de Membros de Elenco',
        path: '/castmember',
        component: CastMemberList,
        exact: true

    },
    {
        name: 'genres.list',
        label: 'Lista de Gêneros',
        path: '/genres',
        component: GenreList,
        exact: true

    },
];

export default routes;
