import React, {useCallback, useEffect, useState} from 'react';
import axios, {endpoints} from "../axios";

interface IChatCreateResponseSuccess {

}
interface IChatCreateResponseError {
    response: {
        data: []
    }
}

function useCreateChat() {
    const [response, setResponse] = useState({});
    const [isFetching, setIsFetching] = useState(false);
    const [error, setError] = useState([]);

    const sendRequest = useCallback( (nickname: string, title: string) => {
        if (isFetching) return
        setIsFetching(true);
        setError([]);

        const fd = new FormData();
        fd.append('nickname', nickname);
        fd.append('title', title);

        axios.post(endpoints.new, fd)
            .then((response: IChatCreateResponseSuccess) => {
                setResponse(response);
                setIsFetching(false);
            })
            .catch((error: IChatCreateResponseError) => {
                if (error.response && 'data' in error.response) {
                    setError(error.response.data);
                } else {
                    // @ts-ignore
                    setError(['Неизвестная ошибка']);
                }
                setIsFetching(false);
            });

    }, [isFetching]);

    return {
        isFetching,
        sendRequest,
        error,
        response
    };
}

export default useCreateChat;
