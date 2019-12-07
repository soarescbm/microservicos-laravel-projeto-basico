import React from 'react';
import logo from './logo.svg';
//import './App.css';
import Button from "@material-ui/core/Button";
import {Navbar} from "./components/Navbar";
import {BrowserRouter} from "react-router-dom";
import {Box} from "@material-ui/core";
import AppRoute from "./routes/AppRoute";
import Breadcrumbs from "./components/Breadcrumbs";

const App: React.FC = () => {
  return (
      <React.Fragment>
            <BrowserRouter>
                <Navbar/>
                <Box paddingTop={'100px'}>
                    <Breadcrumbs/>
                    <AppRoute/>
                </Box>
            </BrowserRouter>
      </React.Fragment>
  );
}

export default App;
