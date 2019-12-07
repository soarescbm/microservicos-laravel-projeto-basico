// @flow 
import * as React from 'react';
import {AppBar, Toolbar, Typography, Button, Theme, IconButton} from "@material-ui/core";
import Logo from "../../static/img/logo.png";
import makeStyles from "@material-ui/core/styles/makeStyles";
import {Menu} from "./Menu";
const useStyles = makeStyles((theme:Theme) => ({
    toolbar: {
        backgroundColor: '#000000'
    },
    title: {
        flexGrow: 1,
        textAlign: 'center'
    },
    logo: {
        width: 100,
        [theme.breakpoints.up('sm')]:{
           width: 120
        }
    }
}))

export const Navbar: React.FC = () => {
    const classes = useStyles();

    return (
        <AppBar>
            <Toolbar className={classes.toolbar}>
                <Menu/>
                <Typography className={classes.title}>
                    <img src={Logo} alt="CodeFlix"  className={classes.logo}/>
                </Typography>
                <Button color="inherit">
                    Login
                </Button>
            </Toolbar>
        </AppBar>
    );
};