<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;
use App\Models\PaymentTask;
use App\Models\ProspectTask;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Constructor accepts any of the Task types
     *
     * @param Task|PaymentTask|ProspectTask $task
     */
    public function __construct($task)
    {
        // Ensure that the task is a valid instance of Task, PaymentTask, or ProspectTask
        if (!($task instanceof Task || $task instanceof PaymentTask || $task instanceof ProspectTask)) {
            throw new \InvalidArgumentException("Invalid task type");
        }

        $this->task = $task;
    }

    /**
     * Define how the notification will be delivered (via mail and database)
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Send both mail and database notifications
        return ['mail', 'database'];
    }

    /**
     * Define the notification data to be stored in the database
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // Log the notification to verify it's being triggered
        \Log::debug('Sending notification for task: ' . class_basename($this->task));

        $taskType = class_basename($this->task); // Dynamically get the class name of the task

        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'task_type' => $taskType, // Add the task type to distinguish between Task, PaymentTask, and ProspectTask
            'assigned_by' => $this->task->assignedBy->username ?? 'System', // Assuming `assignedBy` is an attribute
            'message' => 'A new ' . strtolower($taskType) . ' has been assigned to you: ' . $this->task->name,
        ];
    }

    /**
     * Define the email content for the notification
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        // Dynamically get the task type (Task, PaymentTask, ProspectTask)
        $taskType = class_basename($this->task);

        return (new MailMessage)
            ->subject('New ' . $taskType . ' Assigned')
            ->line('A ' . strtolower($taskType) . ' has been assigned to you: ' . $this->task->name)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Please check the task and complete it by the due date.');
    }
}
