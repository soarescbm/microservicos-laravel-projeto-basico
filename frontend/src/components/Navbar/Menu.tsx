// @flow
import * as React from 'react';
import {IconButton, Menu as MuiMenu, MenuItem,  Toolbar} from "@material-ui/core";
import MenuIcon from '@material-ui/icons/Menu';
import routes, {MyRouterProps} from "../../routes";
import {Link} from "react-router-dom";

const listRoutes = [
    'dashboard',
    'categories.list',
    'castmembers.list',
    'genres.list'
];

const menuRautes = routes.filter(route => listRoutes.includes(route.name));


export const Menu = () => {
    const [anchorEl, setAnchorEl] = React.useState(null);
    const open = Boolean(anchorEl);
    const handleOpen = (event:any) => setAnchorEl(event.currentTarget);
    const handleClose =  () => setAnchorEl(null);
    return (
        <React.Fragment>
            <IconButton
                color="inherit"
                edge="start"
                aria-label="open drawer"
                aria-controls="menu-appbar"
                aria-haspopup="true"
                onClick={handleOpen}
            >
                <MenuIcon/>
            </IconButton>
            <MuiMenu
                id="menu-appbar"
                open={open}
                anchorEl={anchorEl}
                onClick={handleClose}
                anchorOrigin={{vertical:"bottom", horizontal:"center"}}
                transformOrigin={{vertical:"top", horizontal:"center"}}
                getContentAnchorEl={null}
            >
                {
                    listRoutes.map(
                        (routeName, key) => {
                            const route =  menuRautes.find(route => route.name === routeName) as MyRouterProps;
                            return (
                                <MenuItem key={key} component={Link} to={route.path as string} onClick={handleClose}>
                                    {route.label}
                                </MenuItem>
                            )
                        }
                    )
                }


            </MuiMenu>
        </React.Fragment>
    );
};