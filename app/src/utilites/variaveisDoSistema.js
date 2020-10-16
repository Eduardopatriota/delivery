// const serveSMS = 'https://jardersilva.com.br/deliveryV2';
// const serve    = 'https://jardersilva.com.br/deliveryV2';
// const serveIMG = 'https://jardersilva.com.br/deliveryV2/up';


const serveSMS = 'https://jardersilva.com.br/delivery3';
const serve    = 'https://jardersilva.com.br/delivery3';
const serveIMG = 'https://jardersilva.com.br/delivery3/up';

export default  class VariaveisGlobais {
    get getHost(){
        return serve;
    }

    get getHostSMS(){
        return serveSMS;
    }

    get getHostIMG(){
        return serveIMG;
    }
}