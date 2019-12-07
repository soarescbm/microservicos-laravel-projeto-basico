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

   // let dataUI = {"data":[{"id":"030312cf-9caf-46c5-a8b3-5735cc8ebf87","name":"LavenderBlush","description":"Nihil pariatur assumenda illo voluptatem.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"0a7952c3-1f6d-4de7-9553-c5180b3e82ac","name":"Tomato","description":"Dicta eligendi quis et reiciendis quo.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"0b2e2b36-a3fb-4a90-b24d-ad5aa77711d3","name":"LightSeaGreen","description":null,"is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"0c412a36-8219-4836-806b-e3b7f2b927a2","name":"PowderBlue","description":"Aut in et ipsa illum dignissimos ut omnis.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"0d0e2f0f-49dd-4957-9c1a-ee6742a2906e","name":"Tan","description":"Et distinctio ad eveniet sunt.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"0f745e08-d1f1-452c-86e9-7b857084a7d7","name":"HotPink","description":"Dicta voluptates illum autem voluptatibus odio asperiores.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"116ea3ae-3094-4e4b-91b8-a327344ade03","name":"GoldenRod","description":"Consequatur aut nisi enim quas.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"11a07b4a-7894-4e1f-81d5-c2c59d9b26df","name":"Peru","description":null,"is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"133dc779-9295-45bf-830f-d3c823f51b2e","name":"Gainsboro","description":"Laudantium et doloremque nisi qui aut.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"161c6df4-c691-46b2-8074-e46870a6cbce","name":"Brown","description":null,"is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"16aa8a9d-f520-4652-9432-e1db3fb5f851","name":"Crimson","description":null,"is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"1747c426-e1a1-40c8-8159-f1b5a75205d8","name":"HoneyDew","description":"Sit dolorum velit est non veritatis impedit.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"204edb2c-8313-4b9d-8016-080b3cc7bd1c","name":"GhostWhite","description":"At qui non quibusdam quis.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"205e0db5-1edf-4103-806e-37b7412c4b7e","name":"LightSkyBlue","description":"Voluptatum praesentium sint nobis quia.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"},{"id":"22cd3d6d-7454-4438-a393-3f2b360357d6","name":"DeepSkyBlue","description":"Nostrum vel tenetur consequuntur quo cum.","is_active":true,"deleted_at":null,"created_at":"2019-10-29 12:04:12","updated_at":"2019-10-29 12:04:12"}],"links":{"first":"http:\/\/localhost:8000\/api\/categories?page=1","last":"http:\/\/localhost:8000\/api\/categories?page=7","prev":null,"next":"http:\/\/localhost:8000\/api\/categories?page=2"},"meta":{"current_page":1,"from":1,"last_page":7,"path":"http:\/\/localhost:8000\/api\/categories","per_page":15,"to":15,"total":100}};

    useEffect(() => {
        httpVideo.get('categories').then(
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