// @flow 
import * as React from 'react';
import {useEffect, useState} from "react";
import {httpVideo} from "../../util/http";
import MUIDataTable, {MUIDataTableColumn, MUIDataTableColumnDef} from "mui-datatables"
import {Chip} from "@material-ui/core";
import format from "date-fns/format";
import parseISO from "date-fns/parseISO";

const columnDefinitions: MUIDataTableColumn[] = [
    {
        name: 'name',
        label: 'Nome'
    },
    {
        name: 'categories',
        label: 'Categorias',
        options: {
            customBodyRender: (value, tableMeta, updateValue) => {
                 return value.map(value => value.name).join(', ');
            }
        }
    },
    {
        name: 'is_active',
        label: 'Ativo?',
        options: {
            customBodyRender(value, tableMeta, updateValue){
                return value? <Chip label={'Sim'} color={"primary"}/>:<Chip label={'Sim'} color={"secondary"}/>;
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




type Props = {
    
};
const Table = (props: Props) => {

    const [data, setData] = useState([]);

    useEffect(() => {
        httpVideo.get('genres').then(
                response => setData(response.data.data)
             )
    },[])

    return (
        <MUIDataTable
            columns={columnDefinitions}
            data={data}
            title={'Listagem de Gêneros'}
            >
        </MUIDataTable>
    );
};

export default Table;