import { Alert } from 'react-native';

export function MAlert(Msg, Titulo, Cancelable) {
    Alert.alert(
        Titulo + '',
        Msg + '',
        [
            { text: 'OK', onPress: () => console.log('OK Pressed') },
        ],
        { cancelable: Cancelable }
    );
}

export function MAlertPerg(Msg, Titulo, Cancelable, Teste) {
    let Exit = false;
    Alert.alert(
        Titulo,
        Msg,
        [
            {
                text: 'Cancelar',
                onPress: () => { Exit = false },
                style: 'cancel',
            },
            { text: 'OK', onPress: () =>  Teste },
        ],
        { cancelable: Cancelable },
    );
}