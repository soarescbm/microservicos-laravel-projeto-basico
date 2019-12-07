// @flow 
import * as React from 'react';
import {useEffect, useState} from "react";
import {httpVideo} from "../../util/http";
import MUIDataTable, {MUIDataTableColumn, MUIDataTableColumnDef} from "mui-datatables"
import {Chip} from "@material-ui/core";
import format from "date-fns/format";
import parseISO from "date-fns/parseISO";

const CastMemberTypeMap = {
    1: 'Diretor',
    2: 'Ator'
}
const columnDefinitions: MUIDataTableColumn[] = [
    {
        name: 'name',
        label: 'Nome'
    },

    {
        name: 'type',
        label: 'Tipo',
        options: {
            customBodyRender: (value, tableMeta, updateValue) => {
                return CastMemberTypeMap[value];
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
        httpVideo.get('cast_members').then(
                response => setData(response.data.data)
             )
    },[])

    return (
        <MUIDataTable
            columns={columnDefinitions}
            data={data}
            title={'Listagem de Membros de Elenco'}
            >
        </MUIDataTable>
    );
};

export default Table;