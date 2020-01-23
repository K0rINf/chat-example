import React from "react";
import {Button, Jumbotron} from "react-bootstrap";
import {Link} from "react-router-dom";

function Home() {
    console.log(process.env);
    return (
        <Jumbotron>
            <h1>Анонимные чата</h1>
            <p>
                Создайте новый чат либо подключитесь к уже существующему.
            </p>
            <p>
                Для подключение к существующему чату, у вас должна быть прямая ссылка на чат.
            </p>
            {/*<p>*/}
            {/*    Публичные чаты:*/}
            {/*</p>*/}
            {/*<ul>*/}
            {/*    <li>123</li>*/}
            {/*    <li>123123</li>*/}
            {/*    <li>123123</li>*/}
            {/*</ul>*/}
            <p>
                <Link to="/new">
                    <Button variant="primary">Новый чат</Button>
                </Link>
            </p>
        </Jumbotron>
    );
}

export default Home;
