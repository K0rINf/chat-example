import React, {Component} from 'react';
import {Col, Container, Modal, Row} from "react-bootstrap";
import MessageList, {IMessage} from "../components/MessageList";
import MessageForm from "../components/MessageForm";
import axios, {endpoints} from "../axios";
import NickNameForm from "../components/NickNameForm";
import MemberList, {IMember} from "../components/MemberList";

interface IChatResponse {
    data: {
        id: number,
        code: string,
        title: string,
        createdAt: {
            date: string,
            timezone_type: number,
            timezone: string,
        },
        members: IMember[] | any,
    }
}

type ChatState = {
    chat:string | any,
    nickname:string | null,
    title:string | null,
    showNickname:boolean,
    isShowModal:boolean,
    modalText:string,
    messages: IMessage[],
    members: IMember[],
    socket:null|object,
}


class Chat extends Component<{}, ChatState> {
    [x: string]: any;

    public state: ChatState = {
        chat: '',
        nickname: null,
        title: null,
        showNickname: true,
        isShowModal: true,
        modalText: '',
        messages: [],
        members: [],
        socket: null,
    };

    constructor(props: any) {
        super(props);
        const chat = props.match.params.code;
        this.state = {
            ...this.state,
            chat,
            nickname: localStorage.getItem(chat),
        };
    }

    componentDidMount(): void {
        this.onLoadDetail();
    }

    // componentDidUpdate(prevProps) {
        // // Популярный пример (не забудьте сравнить пропсы):
        // if (this.props.userID !== prevProps.userID) {
        //     this.fetchData(this.props.userID);
        // }
    // }

    onLoadDetail = () => {
        const {chat, nickname} = this.state;

        this.setState({
            modalText: 'Подключаюсь...',
        })

        axios.get(endpoints.detail(chat), {
                    params: {
                        nickname,
                    }
                })
                    .then(async (response: IChatResponse) => {

                        const ws = new WebSocket('ws://127.0.0.1:9001');
                        ws.onopen = async (e: Event) => {
                            this.setState({
                                showNickname: false,
                                isShowModal: false,
                                socket: ws,
                                title: response.data.title,
                                members: response.data.members,
                                messages: await this.loadHistory(chat, nickname),
                            })

                            ws.send(JSON.stringify({
                                type: 'subscribe',
                                body: '',
                                nickname,
                                chat,
                            }));
                        };

                        ws.onclose = (event: CloseEvent) => {
                            this.setState({
                                showNickname: false,
                                isShowModal: true,
                                modalText: 'Разрыв соединения. Повторите подключение.' + 'Код: ' + event.code + ' причина: ' + event.reason,
                            })
                            setTimeout(() => window.location.reload(), 3000);
                        };

                        ws.onmessage = (event: MessageEvent) => {
                            const {messages} = this.state;
                            // @ts-ignore
                            messages.push(JSON.parse(event.data));
                            this.setState({
                                messages,
                            })
                        };

                        ws.onerror = (error: Event) => {
                            console.log("Ошибка ", error);
                        };

                    })
                    .catch((error: any) => {
                        this.setState({
                            showNickname: true,
                            isShowModal: true,
                            modalText: 'Ошибка подключения к чату',
                        })
                    });
    }

    loadHistory = async (code: string, nickname: string| null) => {
        const response = await axios.get(endpoints.history(code), {
            params: {
                nickname,
            }
        });

        return response.data;
    };

    onSubmitMessage = ((body: string) => {
        const {nickname , chat, socket} = this.state;
        const message = {
            body,
            type: 'message',
            nickname,
            chat,
        };
        // @ts-ignore
        if (socket !== null && socket.readyState === WebSocket.OPEN) {
            // @ts-ignore
            socket.send(JSON.stringify(message));
        }
    })

    onSubmitNickname = (nickname: string) => {
        const {chat} = this.state;

        // @ts-ignore
        this.setState({
            nickname,
            showNickname: false,
        })

        localStorage.setItem(chat, nickname);
        this.onLoadDetail();
    }

    onAddMember = (member: string) => {
        const {chat, nickname, members} = this.state;

        const fd = new FormData();
        fd.append('chat', chat);
        // @ts-ignore
        fd.append('nickname', nickname);
        fd.append('member', member);
        axios.post(endpoints.members(chat), fd)
            .then((response:any) => {

                members.push(response.data);
                this.setState({
                    members
                })

            })
            .catch()
    }

    render() {
        const {messages, members, isShowModal, modalText, showNickname, chat, title } = this.state;
    return (
        <div className="chat-container">
            <Container>
                <h1>{title}</h1>
                <Row>
                    <Col md={{span: 9}}>
                        <MessageList
                            messages={messages}
                        />
                        <MessageForm
                            onSubmit={this.onSubmitMessage}
                        />
                    </Col>
                    <Col md={{span: 3}}>
                        <MemberList
                            members={members}
                        />
                        <NickNameForm
                            title="Добавить"
                            onSubmit={(nickname: string) => this.onAddMember(nickname)}
                        />
                    </Col>
                </Row>
            </Container>
            <Modal
                show={isShowModal}
                backdrop='static'
                centered={true}
                aria-labelledby="example-custom-modal-styling-title"
            >
                <div className="text-center">{modalText}</div>
                {showNickname && (
                    <NickNameForm
                        title="Войти"
                        onSubmit={(nickname: string) => this.onSubmitNickname(nickname)}
                    />
                )}
            </Modal>
        </div>
    )}
}

export default Chat;
