import React, {useState} from "react";
import {Button, Form} from "react-bootstrap";

interface IMessageFormProps {
    onSubmit: (message: string) => void,
}

function MessageForm({onSubmit}: IMessageFormProps) {

    const [validated, setValidated] = useState(false);
    const [btnTitle, setBtnTitle] = useState('Отправить');
    const [isFetching, setFetching] = useState(false);

    const handleSubmit = (event: any) => {
        event.preventDefault();
        event.stopPropagation();

        const form = event.currentTarget;
        if (form.checkValidity() !== false) {
            setFetching(true);
            setValidated(true);
            setBtnTitle('Отправляется');
            onSubmit(form.elements.message.value);
            form.elements.message.value = '';
            setValidated(false);
            setFetching(false);
            setBtnTitle('Отправить');
        }
    };

    return (
        <Form
            className="message-form"
            noValidate
            validated={validated}
            onSubmit={handleSubmit}
        >
            <Form.Group controlId="message">
                <Form.Label>Сообщение</Form.Label>
                <Form.Control
                    disabled={isFetching}
                    as="textarea"
                    rows="3"
                />
            </Form.Group>
            <Button variant="primary" disabled={isFetching} type="submit">{btnTitle}</Button>
        </Form>
    )
}

export default MessageForm;

