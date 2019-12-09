import  {RouteProps} from "react-router-dom";
import Dashboard from "../pages/Dashboard";
import CategoryList from "../pages/category/PageList";
import CategoryForm from "../pages/category/PageForm";
import CastMemberList from "../pages/castmember/PageList";
import CastMemberForm from "../pages/castmember/PageForm";
import GenreList from "../pages/genre/PageList";
import GenreForm from "../pages/genre/PageForm";


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
        component: CategoryForm,
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
        name: 'castmembers.create',
        label: 'Criar Membro de Elenco',
        path: '/cast-member/create',
        component: CastMemberForm,
        exact: true

    },
    {
        name: 'genres.list',
        label: 'Lista de Gêneros',
        path: '/genres',
        component: GenreList,
        exact: true

    },
    {
        name: 'genre.create',
        label: 'Criar Gênero',
        path: '/genre/create',
        component: GenreForm,
        exact: true

    },
];

export default routes;
