class ButtonUpload
{
    constructor( id )
    {
        this.element = document.getElementById( id );
        this.url = '';

        if ( this.exist() )
        {
            this.color = this.element.style.backgroundColor;

            this.states = {
                'default' : this.element.innerHTML,
                'empty' : 'Файлы не выбраны!',
                'error' : 'Загружены не все файлы!',
                'complete' : 'Все файлы загружены!',
                'limit' : 'Первышен максимум файлов!',
                'limitSize' : 'Первышен максимальный размер файла!',
                'update' : 'Проверка файла!',
                'uploadError': 'Ошибка создания структуры'
            };

            this.default();
        }
    }

    exist()
    {
        return this.element != null;
    }

    default( label)
    {
        this.element.innerHTML = label ? label : this.states.default;
        this.element.style.backgroundColor = this.color;
        this.element.disabled = false;
    }

    returnToDefault( duration, label )
    {
        let self = this;

        setTimeout(function() {
            self.element.innerHTML = label ? label : self.states.default;
            self.element.style.backgroundColor = self.color;
            self.element.disabled = false;
        }, duration);
    }

    complete( satate )
    {
        this.element.innerHTML = satate;
        this.element.style.backgroundColor = '#07d91c';
        this.element.disabled = true;
    }

    warning( satate )
    {
        this.element.innerHTML = satate;
        this.element.style.backgroundColor = '#fc8c03';
        this.element.disabled = true;
    }

    error( satate )
    {
        this.element.innerHTML = satate;
        this.element.style.backgroundColor = '#d70d0d';
        this.element.disabled = true;
    }

    progress( label )
    {
        this.element.disabled = true;
        this.element.style.backgroundColor = '#19349450';
        this.element.innerHTML = label;
    }

    setUrl( url )
    {
       this.url = url;
    }

    hasUrl()
    {
       return this.url.length > 0;
    }

    redirect()
    {
       location = this.url;
    }
}
