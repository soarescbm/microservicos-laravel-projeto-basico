// @flow
import * as React from 'react';
import {Box, Container, Fab, Typography} from "@material-ui/core";
import makeStyles from "@material-ui/core/styles/makeStyles";
import {Link} from "react-router-dom";
import AddIcon from "@material-ui/core/SvgIcon/SvgIcon";

const useStyles = makeStyles({
    title: {
        color: '#999999',
        fontSize: 20
    }

})
type PageProps = {
    title: string;
};
const Page: React.FC<PageProps> = (props) => {
    const classes = useStyles();
    return (

            <Container>
                <Typography className={classes.title}>
                    {props.title}
                </Typography>
                {props.children}
            </Container>

    );
};
export default Page;