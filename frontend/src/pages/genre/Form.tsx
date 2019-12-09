import * as React from "react";
import {TextField, Checkbox, Box, Button, Theme, RadioGroup, MenuItem} from "@material-ui/core";
import {ButtonProps} from "@material-ui/core/Button";
import makeStyles from "@material-ui/core/styles/makeStyles";
import useForm from "react-hook-form";
import {watch} from "fs";
import {useEffect, useState} from "react";
import genreHttp from "../../util/http/genre-http";
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


    const [categories, setCategories] =  useState<any[]>([]);
    const {register, handleSubmit, getValues, setValue, watch } = useForm(
        {
            defaultValues: {
                categories_id: []
            }
        });

    useEffect(()=> {
        register({
            name: 'categories_id'
        })
    }, [register])

    useEffect(()=> {
        categoryHttp
            .list()
            .then(response => setCategories(response.data.data))
    }, [])

    function onSubmit(formData, event){
        genreHttp.create(formData)
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
                select
                name='categories_id'
                value={watch('categories_id')}
                label='Categorias'
                fullWidth
                variant='outlined'
                margin={'normal'}
                onChange={(e) => {
                    setValue('categories_id', e.target.value)
                }}
                SelectProps={{
                    multiple: true
                    }
                }
            >
                <MenuItem value={''} disabled>
                    <em>Selecione categorias</em>
                </MenuItem>

                {
                    categories.map(
                        (category, key) => (
                            <MenuItem key={key} value={category.id}>{category.name}</MenuItem>
                        )
                    )
                }


            </TextField>

            <Box dir={'rtl'}>

                <Button {...ButtonProps} color={"primary"} onClick={() => onSubmit(getValues(), null)}> Salvar </Button>
                <Button {...ButtonProps} type={'submit'}> Salvar e continuar editando </Button>
            </Box>
        </form>
    );
}
