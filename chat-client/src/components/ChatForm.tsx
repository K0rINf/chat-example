import React, {useEffect, useState} from "react";
import {Alert, Button, Form} from "react-bootstrap";
import useCreateChat from "../hooks/useCreateChat";
import {endpoints} from "../axios";

function CharForm() {
    const [validated, setValidated] = useState(false);
    const {isFetching, sendRequest, error, response} = useCreateChat();

    useEffect(() => {
        console.log(response);
        console.log(window.location);
        if ('data' in response) {
            //@ts-ignore
            const { code, author } = response.data;
            localStorage.setItem(code, author);
            window.location.href = endpoints.detail(code);
        }

    }, [response]);

    const handleSubmit = (event: any) => {
        event.preventDefault();
        event.stopPropagation();

        const form = event.currentTarget;
        if (form.checkValidity() !== false) {
            setValidated(true);
            sendRequest(form.elements.nickname.value, form.elements.title.value);
        }
    };

    const btnTitle = (isFetching) ? 'Чат создается' : 'Создать чат';

    return (
        <Form noValidate validated={validated} onSubmit={handleSubmit}>
            <Form.Group controlId="title">
                <Form.Label>Название чата</Form.Label>
                <Form.Control
                    disabled={isFetching}
                    required
                    type="text"
                    placeholder="Название"
                    defaultValue=""
                />
            </Form.Group>
            <Form.Group controlId="nickname">
                <Form.Label>Ваш никнейм</Form.Label>
                <Form.Control
                    disabled={isFetching}
                    required
                    type="text"
                    placeholder="Nickname"
                    defaultValue=""
                />
            </Form.Group>
            <Button disabled={isFetching} type="submit">{btnTitle}</Button>
            <Alert show={Object.keys(error).length > 0} variant="danger" style={{marginTop: '20px'}}>
                Ошибка созания чата:
                <ul className={'list-unstyled'}>
                    {Object.keys(error).map((field:any) => (
                        <li key={field}>{field}:{error[field]}</li>
                    ))}
                </ul>
            </Alert>
        </Form>
    )
}

export default CharForm;
