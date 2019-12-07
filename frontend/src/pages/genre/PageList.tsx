// @flow
import * as React from 'react';
import Page from "../../components/Page";
import {Link}  from 'react-router-dom';
import {Box, Fab} from "@material-ui/core";
import AddIcon from "@material-ui/icons/Add";
import Table from "./Table";


const PageList = () => {

    return (

        <Page title="Listagem de Gêneros">

            <Box dir={'rtl'}>
                <Fab
                    title="Adicionar Gênero"
                    size="small"
                    component={Link}
                    to="/genre/list"
                >
                    <AddIcon/>
                </Fab>
            </Box>
            <Box>
                <Table/>
            </Box>
        </Page>



    );
};

export default PageList;