import * as React from "react";
import {
    TextField,
    Checkbox,
    Box,
    Button,
    Theme,
    RadioGroup,
    FormControlLabel,
    Radio,
    FormControl, FormLabel
} from "@material-ui/core";
import {ButtonProps} from "@material-ui/core/Button";
import makeStyles from "@material-ui/core/styles/makeStyles";
import useForm from "react-hook-form";
import castMemberHttp from "../../util/http/cart-member-http";
import {useEffect} from "react";


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

    const {register, handleSubmit, getValues, setValue} = useForm();

    function onSubmit(formData, event){
        castMemberHttp.create(formData)
            .then((ressponse) => console.log(ressponse))
    }

    useEffect(()=> {
        register({
            name: 'type'
        })
    }, [register])

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <TextField
                name='name'
                inputRef={register}
                label='Nome'
                fullWidth
                variant='outlined'
            />
            <FormControl margin={'normal'}>
                <FormLabel component={'legend'}>Tipo</FormLabel>
                <RadioGroup
                    name='type'
                    onChange={(e) => {
                        setValue('type', parseInt(e.target.value))
                    }}
                >
                    <FormControlLabel value={'1'} control={<Radio/>} label='Diretor'/>
                    <FormControlLabel value={'2'} control={<Radio/>} label='Ator'/>
                </RadioGroup>
            </FormControl>

            <Box dir={'rtl'}>

                <Button {...ButtonProps} color={"primary"} onClick={() => onSubmit(getValues(), null)}> Salvar </Button>
                <Button {...ButtonProps} type={'submit'}> Salvar e continuar editando </Button>
            </Box>
        </form>
    );
}
