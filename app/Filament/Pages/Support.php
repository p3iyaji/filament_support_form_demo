<?php

namespace App\Filament\Pages;


use App\Mail\SupportForm;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use App\Models\Support as Supp;
use Illuminate\Support\Facades\Mail;

class Support extends Page implements HasForms
{
     use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.support';

    protected static string $resource = Support::class;

    public $name, $email, $message, $subject;


    public function form(Form $form): Form{
        return $form->schema([
                  TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                  TextInput::make('email')
                      ->email()
                    ->required()
                    ->maxLength(255),
                  TextInput::make('subject')
                      ->required(),
                  Textarea::make('message')
                      ->rows(10)
                      ->cols(20)
                      ->required(),

                ])
            ->statePath('data');
    }


    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Send')
                ->submit('save'),
//            Action::make('cancel')
//                ->label('Cancel')
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('User registered')
            ->body('The user has been created successfully.');
    }
    public function save(): void
    {
        try{
            $data = $this->form->getState();

            $record = new Supp();
            $record->name = $data['name'];
            $record->subject = $data['subject'];
            $record->message = $data['message'];
            $record->email = $data['email'];
            $record->save();

            //getting data for the email
            $this->name = $record->name;
            $this->email = $record->email;
            $this->subject = $record->subject;
            $this->message = $record->message;

            Mail::to('myadmintest@test.com')->send(new SupportForm($this->name, $this->email, $this->subject, $this->message));
            //upon successfully mail delivery
            Notification::make()
                ->title('Message sent successfully')
                ->success()
                ->send();
            $this->form->fill();
        }catch(Exception $ex){
            session()->flash('error', 'Something went wrong');
        }
    }

}
