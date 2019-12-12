// @flow 
import * as React from 'react';
import {useEffect, useState} from "react";
import {httpVideo} from "../../util/http";
import MUIDataTable, {MUIDataTableColumn, MUIDataTableColumnDef} from "mui-datatables"
import {Chip} from "@material-ui/core";
import format from "date-fns/format";
import parseISO from "date-fns/parseISO";
import categoryHttp from "../../util/http/category-http";
import {BadgeNo, BadgeYes} from "../../components/Badge";

const columnDefinitions: MUIDataTableColumn[] = [
    {
        name: 'name',
        label: 'Nome'
    },

    {
        name: 'is_active',
        label: 'Ativo?',
        options: {
            customBodyRender(value, tableMeta, updateValue){
                return value? <BadgeYes/>:<BadgeNo/>
            }
        }
    },
    {
        name: 'created_at',
        label: 'Criado m',
        options: {
            customBodyRender(value, tableMeta, updateValue){
                return <span> {format(parseISO(value), 'dd/MM/yyyy')}</span>;
            }
        }
    },

];


interface Category {
    id: string,
    name: string,
    description: string
}


type Props = {
    
};
const Table = (props: Props) => {

    const [data, setData] = useState<Category[]>([]);

    useEffect(() => {
        categoryHttp.list<{data: Category[]}>().then(
            response => setData(response.data.data)
        )
    },[])

    return (
        <MUIDataTable
            columns={columnDefinitions}
            data={data}
            title={'Listagem de Categorias'}
            >
        </MUIDataTable>
    );
};

export default Table;