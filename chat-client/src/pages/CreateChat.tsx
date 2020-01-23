import React, {useState} from "react";
import {Button, Col, Container, Form, Row} from "react-bootstrap";
import CharForm from "../components/ChatForm";

function CreateChat() {
    return (
        <Container>
            <Row>
                <Col md={{ span: 6, offset: 3 }}>
                    <CharForm />
                </Col>
            </Row>
        </Container>
    );
}

export default CreateChat;
