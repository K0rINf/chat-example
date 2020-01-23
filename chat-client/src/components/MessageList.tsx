import React, {useEffect, useRef, useState} from "react";

export interface IMessage {
    id:  number,
    author:  string,
    createdAt: {
        date: string,
        timezone_type: number,
        timezone: string,
    },
    body: string,
}

interface IMessageListProps {
    messages: IMessage[]
}

function MessageList({messages}: IMessageListProps) {
    const messagesListRef = useRef(null);
    useEffect(() => {
        //@ts-ignore
        messagesListRef.current.scrollTo(0, messagesListRef.current.scrollHeight);
    },[messages]);
    return (
        <div className="message-container" ref={messagesListRef}>
            {messages.map((message: IMessage) => (
                <div className="message" key={message.id}>
                    <div>
                        <div className="message-author">{message.author}</div>
                        <div className="message-date">{message.createdAt.date}</div>
                    </div>
                    <div className="message-body">{message.body}</div>
                </div>
            ))}
        </div>
    )
}
export default MessageList;

