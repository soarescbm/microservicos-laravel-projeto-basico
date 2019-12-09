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

            <Box dir={'rtl'} paddingBottom={2}>
                <Fab
                    title="Adicionar Gênero"
                    size="small"
                    color="secondary"
                    component={Link}
                    to="/genre/create"
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