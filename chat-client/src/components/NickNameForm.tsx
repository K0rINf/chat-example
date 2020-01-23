import React, {useState} from "react";
import {Alert, Button, Form} from "react-bootstrap";
import useCreateChat from "../hooks/useCreateChat";


interface INickNameFormProps {
    onSubmit: (nickname: string) => void
    title: string,
}

function NickNameForm(props: INickNameFormProps) {
    const [validated, setValidated] = useState(false);

    const handleSubmit = (event: any) => {
        event.preventDefault();
        event.stopPropagation();

        const form = event.currentTarget;
        if (form.checkValidity() !== false) {
            setValidated(true);
            props.onSubmit(form.elements.nickname.value);
        }
    };

    return (
        <Form noValidate validated={validated} onSubmit={handleSubmit}>
            <Form.Group controlId="nickname">
                <Form.Label>Ваш никнейм</Form.Label>
                <Form.Control
                    required
                    type="text"
                    placeholder="Nickname"
                    defaultValue=""
                />
            </Form.Group>
            <Button type="submit">{props.title}</Button>
        </Form>
    )
}

export default NickNameForm;
