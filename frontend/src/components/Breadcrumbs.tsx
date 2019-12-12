/* eslint-disable no-nested-ternary */
import React from 'react';
import { makeStyles, Theme, createStyles } from '@material-ui/core/styles';
import List from '@material-ui/core/List';
import Link, { LinkProps } from '@material-ui/core/Link';
import ListItem from '@material-ui/core/ListItem';
import Collapse from '@material-ui/core/Collapse';
import ListItemText from '@material-ui/core/ListItemText';
import Typography from '@material-ui/core/Typography';
import ExpandLess from '@material-ui/icons/ExpandLess';
import ExpandMore from '@material-ui/icons/ExpandMore';
import MuiBreadcrumbs from '@material-ui/core/Breadcrumbs';
import { Route, MemoryRouter } from 'react-router';
import { Link as RouterLink } from 'react-router-dom';
import { Omit } from '@material-ui/types';
import { Location } from 'history';
import routes from "../routes";
import RouteParser from "route-parser";
import {Box, Container} from "@material-ui/core";
interface ListItemLinkProps extends LinkProps {
    to: string;
    open?: boolean;
}

const breadcrumbNameMap: { [key: string]: string } = {};
routes.forEach(route => breadcrumbNameMap[route.path as string] = route.label );

function ListItemLink(props: Omit<ListItemLinkProps, 'ref'>) {
    const { to, open, ...other } = props;
    const primary = breadcrumbNameMap[to];


    return (
        <li>
            <ListItem button component={RouterLink} to={to} {...other}>
                <ListItemText primary={primary} />
                {open != null ? open ? <ExpandLess /> : <ExpandMore /> : null}
            </ListItem>
        </li>
    );
}

const useStyles = makeStyles((theme: Theme) =>
    createStyles({

        lists: {
            backgroundColor: theme.palette.background.paper,
            marginTop: theme.spacing(1),
        },
        nested: {
            paddingLeft: theme.spacing(4),
        },
        linkRouter: {
            color: "#4db5ad",
            "&:active, &:focus": {
                color: "#4db5ad",
            },
            "&:hover": {
                color: "#055b52",
            },
        }

    }),
);

interface LinkRouterProps extends LinkProps {
    to: string;
    replace?: boolean;
}

const LinkRouter = (props: LinkRouterProps) => <Link {...props} component={RouterLink as any} />;

export default function Breadcrumbs() {
    const classes = useStyles();

    function makeBreadcrumb(location: Location) {

            const pathnames = location.pathname.split('/').filter(x => x);
            pathnames.unshift('/');
            return (
                <MuiBreadcrumbs aria-label="breadcrumb">

                    {
                        pathnames.map((value, index) => {
                        const last = index === pathnames.length - 1;
                        const to = `${pathnames.slice(0, index + 1).join('/').replace('//','/')}`;
                        const route = Object.keys(breadcrumbNameMap).find(path => new RouteParser(path).match(to));

                        if( route === undefined){
                            return false;
                        }

                        return last ? (
                            <Typography color="textPrimary" key={to}>
                                {breadcrumbNameMap[route]}
                            </Typography>
                        ) : (
                            <LinkRouter to={to} key={to} className={classes.linkRouter}>
                                {breadcrumbNameMap[route]}
                            </LinkRouter>
                        );
                    })
                    }
                </MuiBreadcrumbs>
            );

    }
    return (

            <Container>
                <Box paddingBottom={1} paddingTop={1}>
                    <Route>
                        {
                            ({location}: {location: Location}) => makeBreadcrumb(location)
                        }
                    </Route>
                </Box>

            </Container>
    
    )
}