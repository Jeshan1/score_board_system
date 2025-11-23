import {ref} from 'vue'

export const useSocket=()=> {
    const listen=  (channel,eventName,callback)=>{
        return  window.Echo.channel(channel).listen(eventName,(e)=>callback(e))
    }

    return {
        listen
    }
}