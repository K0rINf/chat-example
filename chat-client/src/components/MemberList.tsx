import React, {useState} from "react";
import {Alert, Button, Form} from "react-bootstrap";
import useCreateChat from "../hooks/useCreateChat";

export interface IMember {
    id: number,
    createdAt: {
        date: string,
        timezone_type: number,
        timezone: string,
    },
    nickname: string,
}

interface IMemberListProps {
    members: IMember[],
}

function MemberList({members}: IMemberListProps) {
    return (
        <div className="member-container">
            Список участников
            <ul>
                {members.map((member: IMember) => (
                    <li key={member.id}><a href="#">{member.nickname}</a></li>
                ))}
            </ul>
        </div>
    )
}
export default MemberList;

