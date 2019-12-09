import * as React from "react";
import {TextField, Checkbox, Box, Button, Theme} from "@material-ui/core";
import {ButtonProps} from "@material-ui/core/Button";
import makeStyles from "@material-ui/core/styles/makeStyles";
import useForm from "react-hook-form";
import categoryHttp from "../../util/http/category-http";

// const useStyles  = makeStyles((theme: Theme) => (
//     {
//         submit: {
//             margin: theme.spacing(1)
//         }
//     }
// ))
const useStyles = makeStyles((theme:Theme) => ({
    submit: {
            margin: theme.spacing(1)
        }


}))

export const Form  = () => {

    const classes = useStyles();

    const ButtonProps : ButtonProps = {
        className: classes.submit,
        color: 'secondary',
        variant: 'contained',

    }

    const {register, handleSubmit, getValues} = useForm({
         defaultValues: {
                is_active: true
             }
         }
    );

    function onSubmit(formData, event){
        categoryHttp.create(formData)
            .then((ressponse) => console.log(ressponse))
    }

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <TextField
                name='name'
                inputRef={register}
                label='Nome'
                fullWidth
                variant='outlined'
            />
            <TextField
                name='description'
                inputRef={register}
                label='Descrição'
                fullWidth
                variant='outlined'
                multiline
                rows={4}
                margin={'normal'}
            />
            <Checkbox
                name={'is_active'}
                color={"primary"}
                inputRef={register}
                defaultChecked
            /> Ativo?
            <Box dir={'rtl'}>

                <Button {...ButtonProps} color={"primary"} onClick={() => onSubmit(getValues(), null)}> Salvar </Button>
                <Button {...ButtonProps} type={'submit'}> Salvar e continuar editando </Button>
            </Box>
        </form>
    );
}
